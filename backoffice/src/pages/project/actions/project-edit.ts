import { Component, OnInit  } from '@angular/core';
import { ProjectService     } from '../../../_providers/projects/project.service';
import { Project            } from '../../../_providers/projects/project.model';
import { ActivatedRoute, Router } from "@angular/router";
@Component({
    selector: 'page-project-edit',
    styleUrls: ['./project-edit.css'],
    templateUrl: './project-edit.html',
    providers: [ ProjectService ]
})
export class EditProjectPage implements OnInit {
    project: Project = {}; hash: string;

    constructor(
        private projectService: ProjectService,
        private router: Router,
        private route: ActivatedRoute
    ){}
    ngOnInit(){
        this.route.params.subscribe(params =>
        {
            this.hash = params['hash'];
            this.loadData()
        });
    }

    loadData(){
        this.projectService.getProject(this.hash).subscribe((success) => this.project = success)
    }
}