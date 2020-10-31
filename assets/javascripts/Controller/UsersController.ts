import * as moment from 'moment'
import 'bootstrap-switch-button';

export default class UsersController{
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
            {   "targets": i++,'name':'t.email','data':'t_email'},
            {   "targets": i++,'name':'p.firstName','data':'p_firstName' },
            {   "targets": i++,'name':'p.lastName','data':'p_lastName'},
            {   "targets": i++,'name':'t.roles','data':'t_roles'},
            {   "targets": i++,'name':'t.createdAt','data':'t_createdAt',
                "render": function ( data, type, full, meta ) {
                    return moment(new Date(data)).format("DD/MM/YYYY");
                }
            },
            {
                "targets": -1,
                'name':'t.id',
                'data':'t_id',
                "render": function ( data, type, full, meta ) {
                    let ch="";
                     ch = '<button class="btn btn-info">Edit</button> ';
                     ch += '<button class="btn btn-info">Delete</button>';
                     ch+='<input type="checkbox"  class="test" data-toggle="switchbutton" checked data-size="xs">';
                    return ch;
                }
            }

        ]
    }

}