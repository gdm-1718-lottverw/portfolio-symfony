import { Component  } from '@angular/core';
import { Router     } from '@angular/router'
// PROVIDERS 
import { Config     } from "../_providers/config"
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'], 
  providers: [Config]
})
export class AppComponent {
  constructor(private router: Router, private config: Config){
      if(config.TOKEN == null || undefined){
        this.navigate('login');
      }
  }

  navigate(page){
      switch (page){
          case 'project':
              if(this.config.loggedIn == true) {
                  this.router.navigate(['/projects']);
              }
              break;
          case 'user':
              if(this.config.loggedIn == true) {
                  this.router.navigate(['/users']);
              }
              break;
          case 'dashboard':
              if(this.config.loggedIn == true) {
                  this.router.navigate(['/']);
              }
              break;
          case 'login':
              this.router.navigate(['/login']);
              break;
          default:
              break;
        }
    }
}
