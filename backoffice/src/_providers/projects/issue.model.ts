export class Issue {
    id?: number;
    hash?: string;
    title?: string;
    description?: string;
    solved?: boolean;
    solvedBy?: string;
    inProgress?: boolean;
    urgent?: boolean;
    createdAt?: string;
    estimatePomodoros?: number;
}