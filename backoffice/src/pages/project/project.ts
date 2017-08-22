import { Component, OnInit  } from '@angular/core';
import { Router             } from '@angular/router'
// SERVICES
import { ProjectService     } from '../../_providers/projects/project.service';
// MODEL
import { Project            } from '../../_providers/projects/project.model';

@Component({
    selector: 'page-project',
    styleUrls: ['./project.css'],
    templateUrl: './project.html',
    providers: [ProjectService]
})

export class ProjectPage implements OnInit{
    projects: Project[] = []; error: boolean = false; errorMessage: string;
    constructor(
        private projectService: ProjectService,
        private router: Router
    ){}

    ngOnInit(){
        this.loadData();
    }

    loadData(){
        this.projectService.getProjects().subscribe(
            (success) =>{
                this.projects = success;
            }, (error) => {
                this.error = true;
                this.errorMessage = "Unable to retrieve the projects. Try again later";
            }
        );
    }
    navigate(hash){
        console.log(hash);
        this.router.navigate(['/project/' + hash]);
    }
}