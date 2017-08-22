// MODULE
import { BrowserModule    } from '@angular/platform-browser';
import { RouterModule     } from '@angular/router'
import { FormsModule      }   from '@angular/forms';
import { NgModule         } from '@angular/core';
import { HttpModule       } from '@angular/http';
import { routes           } from './app.routes';
import { AppComponent     } from './app.component';
import { ChartModule      } from 'angular2-highcharts';
import { HighchartsStatic } from 'angular2-highcharts/dist/HighchartsService';
import { NgbModule        } from '@ng-bootstrap/ng-bootstrap';
import { AlertModule,     } from 'ngx-bootstrap';
import { DatePickerModule } from 'angular-io-datepicker';
import { OverlayModule    } from 'angular-io-overlay';
// PAGES
import { DashboardPage    } from '../pages/dashboard/dashboard';
import { ProjectPage      } from '../pages/project/project';
import { RegisterPage     } from '../pages/authentication/register/register';
import { LoginPage        } from '../pages/authentication/login/login';
import { EditProjectPage  } from '../pages/project/actions/project-edit';
import { UserPage         } from "../pages/users/user";
import { GroupPage        } from "../pages/users/group/group";
// PROVIDERS
import { Config                   } from '../_providers/config';
import { AngularFontAwesomeModule } from 'angular-font-awesome/angular-font-awesome';
import { AgmCoreModule            } from '@agm/core';

declare let require: any;
export function highchartsFactory() {
  const hc = require('highcharts/highstock');
  const dd = require('highcharts/modules/exporting');
  dd(hc);
  return hc;
}
@NgModule({
  declarations: [
    AppComponent,
    ProjectPage, 
    DashboardPage, 
    LoginPage, 
    RegisterPage,
    UserPage,
    EditProjectPage,
    GroupPage
  ],
  imports: [
    RouterModule.forRoot(routes),
    NgbModule.forRoot(),
    AlertModule.forRoot(),
    BrowserModule,
    DatePickerModule,
    OverlayModule,
    FormsModule,
    AngularFontAwesomeModule,
    AgmCoreModule.forRoot({
      apiKey: 'AIzaSyAKZFvtzQFPucszrsKUsj2IgMR2EYnTSIY'
    }),
    HttpModule,
    ChartModule,
  ],
  providers: [HttpModule, Config, {
      provide: HighchartsStatic,
      useFactory: highchartsFactory
    }],
  bootstrap: [AppComponent]
})
export class AppModule { }
