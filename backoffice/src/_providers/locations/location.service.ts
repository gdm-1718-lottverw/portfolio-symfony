
// Imports
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Config } from '../config';

import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';

import { UserLocation } from './user-location.model';

@Injectable()

export class LocationService {

    constructor (public http: Http, private config: Config) {}

    getLocations(): Observable<UserLocation[]> {
        let url = `${this.config.Base_url}/users/location`;
        let options: RequestOptions = new RequestOptions({ headers: new Headers({
            "Authorization": 'Bearer ' + this.config.TOKEN })
        });

        return this.http.get(url, options)
            .map((res:Response) => res.json())
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'))
    }

}
