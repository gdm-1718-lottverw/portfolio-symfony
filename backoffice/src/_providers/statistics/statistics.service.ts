
// Imports
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Config } from '../config';

import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { ProjectStatistics } from './project.model';

@Injectable()

export class StatisticsService {

    constructor (public http: Http, private config: Config) {}

    getProjects(): Observable<ProjectStatistics> {
        let url = `${this.config.Base_url}/project/stats`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

}
