export class Group {
    id?: number;
    name?: string;
}

export class Team {
    id?: number;
    name?: string;
    users?: [
        {
            username?: string;
            firstName?: string;
            lastName?: string;
        }
    ]
}