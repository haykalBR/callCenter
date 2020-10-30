export default class DefaultController{
    getDatableColumnDef(){
        return [
            {
                "targets": -1,
                "render": function ( data, type, full, meta ) {
    
                    var ch = '<button class="btn btn-info">55</button>';
                    return ch;
                }
            }
        ]
    }
    
}
