import { Routes         } from '@angular/router'
import { DashboardPage  } from '../pages/dashboard/dashboard';
import { ProjectPage    } from '../pages/project/project';
import { EditProjectPage    } from '../pages/project/actions/project-edit';
import { LoginPage      } from '../pages/authentication/login/login';
import { RegisterPage   } from '../pages/authentication/register/register';
import { UserPage       } from '../pages/users/user';
import { GroupPage      } from '../pages/users/group/group';

export const routes: Routes = [
    { path: '',                   component: DashboardPage                     },
    { path: 'projects',           component: ProjectPage,     pathMatch:'full' },
    { path: 'project/:hash',     component: EditProjectPage,     pathMatch:'full' },
    { path: 'login',              component: LoginPage,       pathMatch:'full' },
    { path: 'register',           component: RegisterPage,    pathMatch:'full' },
    { path: 'users',              component: UserPage,        pathMatch:'full' },
    { path: 'group',              component: GroupPage,       pathMatch:'full' },
];