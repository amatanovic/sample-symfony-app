<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\PostForm;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_list")
     * @param Request $request
     * @param PostRepository $postRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request, PostRepository $postRepository)
    {
        $postForm = $this->createForm(PostForm::class);
        $postForm->handleRequest($request);

        if ($this->getUser() && $postForm->isSubmitted() && $postForm->isValid()) {
            /** @var Post $post */
            $post = $postForm->getData();
            $post->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'You\'ve shared new post');

            return $this->redirectToRoute('post_list');
        }

        $posts = $postRepository->findAllPosts();

        return $this->render('post/list.html.twig', [
            'postForm' => $postForm->createView(),
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/detail/{id}", name="post_details")
     * @param Post $post
     * @param LikeRepository $likeRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detail(Post $post, LikeRepository $likeRepository)
    {
        $userLikesPost = $likeRepository->findOneBy([
            'post' => $post,
            'user' => $this->getUser()
        ]);

        return $this->render('post/detail.html.twig', [
            'post' => $post,
            'canDelete' => $this->getUser() === $post->getUser(),
            'userLikesPost' => $userLikesPost
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/post/delete/{id}", name="post_delete")
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Post $post)
    {
        if ($post->getUser()->getId() === $this->getUser()->getId()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($post);
            $manager->flush();
            $this->addFlash('success', 'You\'ve deleted the post.');
        } else {
            $this->addFlash('warning', 'You don\'t have permissions to delete this post.');
        }

        return $this->redirectToRoute('post_list');
    }

    /**
     * @param Request $request
     * @param PostRepository $postRepository
     * @param LikeRepository $likeRepository
     * @return JsonResponse
     * @Route("/post/like", name="post_like", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function like(Request $request, PostRepository $postRepository, LikeRepository $likeRepository)
    {
        $postId = $request->request->get('post');
        /** @var Post $post */
        $post = $postRepository->find($postId);
        if ($post) {
            $like = $likeRepository->findOneBy([
                'post' => $post,
                'user' => $this->getUser()
            ]);

            if ($like) {
                $post->removeLike($like);
            } else {
                $like = new PostLike();
                $like->setUser($this->getUser());
                $post->addLike($like);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
        }

        return new JsonResponse([
            'likes' => isset($post) ? count($post->getLikes()) : 0
        ]);
    }

}
