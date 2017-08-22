import { Component, OnInit  } from '@angular/core';
import { Router             } from '@angular/router'
// SERVICES
import { UserService        } from '../../../_providers/user/user.service';
import { GroupService       } from '../../../_providers/group/group.service';
// MODEL
import { Group, Team        } from '../../../_providers/group/group.model';
import { SimpleUser         } from '../../../_providers/user/user.model';


@Component({
    selector: 'page-group',
    styleUrls: ['./group.css'],
    templateUrl: './group.html',
    providers: [ UserService, GroupService ]
})

export class GroupPage implements OnInit{
    groups: Team[] = []; group: Group = {}; users: SimpleUser[] = [];
    error: boolean = false; errorMessage: string;
    overlay: boolean = false; id: number; index: number;

    constructor(
        private userService: UserService,
        private groupService: GroupService,
        private router: Router
    ){}

    ngOnInit(){
        this.loadData();
    }

    loadData(){
        this.groupService.getGroups().subscribe(team => { this.groups = team, console.log(this.groups) });
        this.userService.getSimpleUserProfile().subscribe(user => this.users = user);
    }

    addUserToGroup(i, id, username){
        this.groupService.addUserToGroup(id, username).subscribe(
            (success) => {
                let user = {'username': username}
                this.groups[i]['users'].push(user);
            }, (error) => {
                this.error = true; 
                this.errorMessage = "Something went wrong while adding user to group. Pleas try again later.";
            }
        )
    }
    
    delete(gi, ui, group_id, username){    
        console.log(gi, ui, group_id, username)
        this.groups[gi]['users'].splice(ui, 1);
        this.groupService.removeUserFromGroup(group_id, username).subscribe()
    }

    submit(form){
        if(form.valid){
            this.groups.push(this.group);
            console.log({'group': this.group});
            this.groupService.addGroup({'group': this.group}).subscribe();
            this.overlay = false;
        } else {
            this.error = true;
            this.errorMessage = "Add a name to your group."
        }
        
    }

    navigate(){
        this.router.navigate(['users']);
    }
}