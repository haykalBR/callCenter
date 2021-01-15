import { injectable } from "inversify";

@injectable()
export default class MercureService{

    private messageElt:HTMLElement;
    private progressElt:HTMLElement;
    
    constructor(){
        const eventSource = new EventSource(this.getUrl());
        this.messageElt = document.getElementById('message');
        this.progressElt = document.getElementById('progress');
    }

    getUrl():string{
        const url:URL = new URL(process.env.MERCURE_URL);
        url.searchParams.append('topic', 'csv:123456');
        return url.toString();
    }

    onMessage(e:MessageEvent):void{
        const payload = JSON.parse(e.data);

        if (payload.type === 'progress') {
            if (!payload.data.total) {
                return
            }
            let percentage = (payload.data.current / payload.data.total) * 100 ;
            console.log(percentage,payload.data.current,payload.data.tota);
    
    
            this.progressElt.style.width = `${percentage}%`;
        } else if (payload.type === 'message') {
            this.messageElt.innerHTML = payload.data;
    
        }else if(payload.type ==="error"){
            this.messageElt.innerHTML = payload.data;
        }
    }
}