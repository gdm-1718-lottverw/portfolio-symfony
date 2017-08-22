import { Injectable     } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';

@Injectable()
export class Config {
    Base_url = 'http://nmdad3/api/v1/admin';
    TOKEN: any; username: any;
    loggedIn: boolean = false;
    constructor () {}

}