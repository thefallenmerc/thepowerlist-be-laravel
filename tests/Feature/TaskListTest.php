<?php

namespace Tests\Feature;

use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    function test_if_user_can_see_todays_task_list()
    {
        // create user
        $user = create(User::class);
        // create tasks for the user
        $tasks = create(TaskList::class, ['user_id' => $user->id], 5);
        // hit endpoint
        $response = $this->get('api/tasklist', createHeaders($user));
        // check if response ok
        $response->assertStatus(200);
        // check if count is 5
        $response->assertJsonCount($tasks->count());
    }

    function test_if_user_can_add_a_task_to_todays_list()
    {
        // create user
        $user = create(User::class);
        // make task object
        $task = make(TaskList::class, ['user_id' => $user->id]);

        // hit endpoint
        $response = $this->post('api/tasklist', $task->toArray(), createHeaders($user));
        // check response ok
        $response->assertStatus(200);
        // check response status
        $response->assertJsonFragment(['name' => $task->name]);
    }

    function test_if_user_gets_error_on_adding_6th_task()
    {
        // create user
        $user = create(User::class);
        // create 5 tasks
        create(TaskList::class, ['user_id' => $user->id], 5);

        // request to make 6th task
        $task = make(TaskList::class, ['user_id' => $user->id]);
        $response = $this->post('api/tasklist', $task->toArray(), createHeaders($user));
        // check response 400
        $response->assertStatus(400);
    }

    function test_if_user_can_update_todays_task()
    {
        // create user
        $user = create(User::class);
        // create task
        $tasks = create(TaskList::class, ['user_id' => $user->id], ['is_complete' => false]);
        // try to update task
        $response = $this->put('api/tasklist/' . $tasks->id, ['is_complete' => true], createHeaders($user));
        // check response
        $response->assertStatus(200);
        $response->assertJsonFragment(['is_complete' => true]);
    }

    function test_if_user_can_see_all_tasks()
    {
        // create user
        $user = create(User::class);
        // create task
        $startDate = today()->subDays(10);
        while(!$startDate->isSameDay()) {
            create(TaskList::class, ['user_id' => $user->id]);
            $startDate->addDay();
        }
        // get all tasks
        $response = $this->get('/api/tasklist/all', createHeaders($user));
        // check response
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }
}
