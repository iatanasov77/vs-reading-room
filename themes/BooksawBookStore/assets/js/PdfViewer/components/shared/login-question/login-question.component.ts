import {
    Component,
    ChangeDetectionStrategy,
    Inject,
    EventEmitter,
    Output
} from '@angular/core';
import { ButtonComponent } from '../../shared/button/button.component';
import { TranslateService } from '@ngx-translate/core';

import cssString from './login-question.component.scss';
import templateString from './login-question.component.html';

@Component({
    selector: 'app-login-question',
    changeDetection: ChangeDetectionStrategy.OnPush,
    
    template: templateString || 'Template Not Loaded !!!',
    styles: [cssString || 'Game CSS Not Loaded !!!',],
    standalone: false
})
export class LoginQuestionComponent
{
    @Output() onDoLogin = new EventEmitter<void>();
    @Output() onClose = new EventEmitter<void>();
    
    constructor(
        @Inject( TranslateService ) private translateService: TranslateService
    ) {}
    
    close( event: any ): void
    {
        this.onClose.emit();
    }
    
    doLogin( event: any ): void
    {
        this.onDoLogin.emit();
    }
}
