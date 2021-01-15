import { inject, injectable } from "inversify";
import DatatableFactory from "../../Shared/factory/datatableFactory";
import LocalizationService from "./localization.service";

@injectable()
export default class LocalizationComponent{

    constructor(
        @inject(LocalizationService) localizationService:LocalizationService,
        @inject(DatatableFactory) datatableFactory:DatatableFactory
    ){
        datatableFactory.getDatatable('#localization_table', localizationService);
    }
}