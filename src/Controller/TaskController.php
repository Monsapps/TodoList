<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/tasks')]
class TaskController extends AbstractController
{
    #[Route('', name: 'task_list')]
    public function listAction(TaskService $taskService)
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskService->getTasksTodoList()]);
    }

    #[Route('/done', name: 'task_done_list')]
    public function listDoneAction(TaskService $taskService)
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskService->getTasksDoneList()]);
    }

    #[Route('/create', name: 'task_create')]
    public function createAction(Request $request, TaskService $taskService)
    {
        $task = $taskService->createTask();

        $this->denyAccessUnlessGranted('add', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $taskService->addTask($task, $this->getUser());

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted('edit', $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $taskService->updateTask($this->getUser());

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted('read', $task);

        $taskService->toggleTask($task, $this->getUser());

        $message = sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle());

        if($task->isDone()) {
            $message = sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle());
        }

        $this->addFlash('success', $message);

        return $this->redirectToRoute('task_list');
    }

    #[Route('/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, TaskService $taskService)
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $taskService->deleteTask($task, $this->getUser());

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
