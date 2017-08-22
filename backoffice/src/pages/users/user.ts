import { Component, OnInit  } from '@angular/core';
import { Router             } from '@angular/router'
// SERVICES
import { UserService     } from '../../_providers/user/user.service';
// MODEL
import { User            } from '../../_providers/user/user.model';
import {forEach} from "@angular/router/src/utils/collection";

@Component({
    selector: 'page-user',
    styleUrls: ['./user.css'],
    templateUrl: './user.html',
    providers: [UserService]
})

export class UserPage implements OnInit{
    users: User[] = []; error: boolean = false; overlay: boolean = false; errorMessage: string; id: number; index: number;
    constructor(
        private userService: UserService,
        private router: Router
    ){}

    ngOnInit(){
        this.loadData();
    }

    loadData(){
        this.userService.getUserProfile().subscribe(
            (success) =>{
                this.users = success;
            }, (error) => {
                this.error = true;
                this.errorMessage = "Unable to retrieve user. Try again later.";
            }
        );
    }

    delete(i, id){
        this.overlay = true;
        this.index = i;
        this.id = id
    }

    confirm(){
        this.users.splice(this.index, 1);
        this.userService.delete(this.id).subscribe();
        this.overlay = false;
    }

    navigate(){
        this.router.navigate(['group']);
    }
}