import { Issue } from './issue.model';
import { Item } from './item.model';
import { Task } from './task.model';
import { Feedback } from './feedback.model';
import {Checklist} from "./checklist.model";

export class Project {
    id?: number;
    title?: string;
    customer?: string;
    hash?: string;
    deadline?: string;
    description?: string;
    finished?: boolean;
    inProgress?: boolean;
    issues?: Issue[];
    checklist?: Checklist;
    tasks?: Task[];
    group?: {
        name: string;
        id: number;
    };
    feedback?: Feedback[];
}