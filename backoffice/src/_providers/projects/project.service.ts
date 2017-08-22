// Imports
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Config } from '../config';

import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { Project } from './project.model';

@Injectable()

export class ProjectService {

    constructor (public http: Http, private config: Config) {}

    getProjects(): Observable<Project[]> {
        let url = `${this.config.Base_url}/projects`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

    getProject(hash): Observable<Project[]> {
        let url = `${this.config.Base_url}/projects/${hash}`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }
}
