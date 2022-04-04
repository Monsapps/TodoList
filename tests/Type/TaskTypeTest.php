<?php

namespace App\Tests\Type;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'titleTest',
            'content' => 'contentTest'
        ];

        $task = new Task();

        $form = $this->factory->create(TaskType::class, $task);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $taskExpected = new Task();
        $taskExpected->setTitle('titleTest');
        $taskExpected->setContent('contentTest');

        $this->assertSame($taskExpected->getTitle(), $task->getTitle());

        $this->assertSame($taskExpected->getContent(), $task->getContent());

    }
}