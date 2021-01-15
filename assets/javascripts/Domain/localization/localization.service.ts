import { injectable } from "inversify";
import Datatable from "../../Shared/interfaces/datatable";

@injectable()
export default class LocalizationService implements Datatable{
    constructor(){}
    getDatableColumnDef():Array<any> {
        let i=0;
        return [
            {   "targets": i++,'name':'t.id','data':'t_id'},
            {   "targets": i++,'name':'t.name','data':'t_name'},
            {   "targets": i++,'name':'t.isoThree','data':'t_isoThree'},
            {
                "targets": -1,
                'name':'t.id',
                'data':'t_id',
                "render": function ( data, type, full, meta ) {
                    let ch="";
                     ch = '<a class="btn btn-info"  href="">Edit</a> ';
                     ch += '<a class="btn btn-info">Delete</a> ';
                     ch+='<input type="checkbox"  class="test" data-toggle="switchbutton" checked data-size="xs">';
                    return ch;
                }
            }

        ]
    }
    getAjax() {
        return {
            url : '/admin/localization/',
            data: function(data) {

                data.join = [
                    // {   "join": "App\\Domain\\localization\\Entity\\City","alias": 'p',"condition": "t.id = p.user"}
                ];
                data.hiddenColumn= [
                    // {   name: 'p.mobile',data: 'p_mobile'}
                ];
                data.customSearch =[
                     {'name':'t.name','value':"",'type':'text'},
                    //  {'name':'t.email','value':$('#search_users_email').val(),'type':'text'},
                    // {'name':'p.gender','value':$('#search_users_gender').val(),'type':'array'}
                  ]
                ;

            }
        }
    }
}