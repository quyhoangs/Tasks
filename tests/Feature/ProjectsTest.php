<?php

namespace Tests\Feature;

use Carbon\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */

    public function only_authenticated_users_can_create_projects()
    {   
        $attributes = Factory('App\Project')->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */

    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();    //không Xử lý Ngoại lệ

        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');             //Kiểm tra có thể gửi 1 yêu cầu post tới url localhost/project và insert các cột vào db không (tạo Modal Project trước khi test khai báo route)

        $this->assertDatabaseHas('projects', $attributes);                              //khẳng định rằng cơ sở dữ liệu có một project bao gồm các thuộc tính là $attributes

        $this->get('/projects')->assertSee($attributes['title']);                      //Khẳng định chuỗi đã cho có chứa trong $response=$this->get('/projects')
    }

    /** @test */

    public function test_a_user_can_view_a_project()
    {   
        $this->withoutExceptionHandling();          //không Xử lý Ngoại lệ
        $project = Factory('App\Project')->create();
        $this->get($project->path()) 
        ->assertSee($project ->title)
        ->assertSee($project ->description);
    }


    /** @test */
    public function test_a_project_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = Factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */

    public function test_a_project_requires_a_description()
    {   
        $this->actingAs(factory('App\User')->create());

        $attributes = Factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
