
export default class DefaultController{
        getAjax(){
        return {
            'url': "/",
            data: {
                join: [
                    {   "join": "App\\Domain\\Membre\\Entity\\Profile","alias": 'p',"condition": "t.id = p.user"}
                ],
                hiddenColumn: [
                    {   name: 'p.mobile',data: 'p_mobile'}
                ]
            }
        }
       }
        getDatableColumnDef(){
           let i =0;
           return [
               {   "targets": i++,'name':'t.id','data':'t_id'},
               {   "targets": i++,'name':'p.address','data':'p_address' },
               {   "targets": i++,'name':'t.enabled','data':'t_enabled'},
               {   "targets": i++,'name':'t.email','data':'t_usern'},
               {   "targets": i++,'name':'t.googleAuthenticatorSecret','data':'t_email'},
               {
                   "targets": -1,
                   'name':'t.email',
                   'data':'t_usern',
                   "render": function ( data, type, full, meta ) {
                       var ch = '<button class="btn btn-info">55</button>';
                       return ch;
                   }
               }
               
               ]
       }
    
}
