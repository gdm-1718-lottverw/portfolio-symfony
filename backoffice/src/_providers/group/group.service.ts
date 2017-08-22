
// Imports
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Config } from '../config';

import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { Group, Team } from './group.model';

@Injectable()

export class GroupService {

    constructor (public http: Http, private config: Config) {
        console.log(this.config.username);
    }
    addGroup(data): Observable<Group> {
        let url = `${this.config.Base_url}/team/create`,
        body: string = JSON.stringify(data),
        options: RequestOptions = new RequestOptions({ headers: new Headers({
                "Authorization": 'Bearer ' + this.config.TOKEN,
                "Content-Type": 'application/json'
            })
        });
        return this.http.post(url, body, options)
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }
    addUserToGroup(id, username): Observable<Team> {
        console.log(this.config.username);
        let url = `${this.config.Base_url}/team/${id}/add/${username}`,
        options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN,
            }) 
        });
        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

    removeUserFromGroup(id, username): Observable<Team> {
        console.log(this.config.username);
        let url = `${this.config.Base_url}/team/${id}/remove/${username}`,
        options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN,
            }) 
        });
        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

    getGroups(): Observable<Team[]> {
        let url = `${this.config.Base_url}/team`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }
}
