
// Imports
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Config } from '../config';

import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { User, SimpleUser } from './user.model';

@Injectable()

export class UserService {

    constructor (public http: Http, private config: Config) {}

    getUserProfile(): Observable<User[]> {
        let url = `${this.config.Base_url}/users`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

    getSimpleUserProfile(): Observable<SimpleUser[]> {
        let url = `${this.config.Base_url}/users/short`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

    delete(id): Observable<User[]> {
        let url = `${this.config.Base_url}/user/${id}/delete`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }
}
