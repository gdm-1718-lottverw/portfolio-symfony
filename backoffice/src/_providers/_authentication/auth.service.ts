// Imports
import { Injectable, ViewChild } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Observable         } from 'rxjs/Rx';
import { Config             } from '../config';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';


@Injectable()
export class AuthService {
  
    constructor (public http: Http, private config: Config) {}

    login(data): Observable<Token[]> {
        let url = 'http://nmdad3/api/login_check',
            body: string = JSON.stringify(data),
            options: RequestOptions = new RequestOptions({
                headers: new Headers({
                    "Content-Type": "application/json"
                })
            });
        return this.http.post(url, body, options)
            .map((res:Response) => {
                this.config.TOKEN = res.json().token,
                this.config.loggedIn = true;
            })
            .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }


}

export class Token {
    token: string;
}
