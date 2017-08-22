import { Component, OnInit } from '@angular/core';
import {Router} from "@angular/router";
// SERVICES
import { AuthService } from '../../../_providers/_authentication/auth.service';
import { Config      } from '../../../_providers/config';
// MODELS
import { Credentials } from '../../../_providers/_authentication/credentials.model';

// PAGES
@Component({
    selector: 'page-login',
    styleUrls: ['./login.css'],
    templateUrl: './login.html',
    providers: [AuthService]
})

export class LoginPage implements OnInit{
    credentials: Credentials = {}; success: boolean = false; successMessage: string;
    errorMessage: string; error:boolean = false;

    constructor(private authService: AuthService, private router: Router, private config: Config){}

    ngOnInit(){}

    login(form){
        if(form.valid){
            this.authService.login(this.credentials).subscribe(
                (success) => {
                    this.success = true;
                    this.config.username = this.credentials._username;
                    this.successMessage = "Login successfull. redirecting you.";
                    this.router.navigate(['/']);
                },
                (error) => {
                    this.error = true;
                    this.errorMessage = "Something went wrong. Try again. Given username or password might be wrong.";
            }
            );
        } else {
            this.error = true;
            if(this.credentials._username == ""){
                this.errorMessage = "Username is not valid try again please.";
            } else if (this.credentials._password == "") {
                this.errorMessage = "Password is not valid try again please";
            }
        }
    }
}
