<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProjectTest extends TestCase
{  
    use RefreshDatabase;

    /** @test */ 
   public function test_it_has_a_path()
   {
      $project = Factory('App\Project')->create();

      $this->assertEquals('/projects/' . $project->id,$project->path());
   }

}
