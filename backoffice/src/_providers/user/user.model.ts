export class User {
    id?: number;
    email?: string;
    enabled?: boolean;
    firstName?: string;
    lastName?: string;
    groups?: [{
        name?: string;
        roles?: [string];
    }];
    lastLogin?: string;
    profile?: {
        id?: number;
        isActive?: boolean;
    };
    roles?:[string];
    username?: string;
}

export class SimpleUser {
    id?: number;
    username?: string;
}