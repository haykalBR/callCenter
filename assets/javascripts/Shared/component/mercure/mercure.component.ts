import { inject, injectable } from "inversify";
import MercureService from "./mercure.service";

@injectable()
export default class MercureComponent{
    private eventSource:EventSource;
    constructor(
        @inject(MercureService) mercureService:MercureService
    ){
        this.eventSource = new EventSource(mercureService.getUrl());
        this.eventSource.onmessage = mercureService.onMessage;
    }
}