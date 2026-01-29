import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

import { MessagesComponent } from './messages/messages.component';
import { ButtonComponent } from './button/button.component';
import { LoginQuestionComponent } from './login-question/login-question.component';

@NgModule({
    declarations: [
        MessagesComponent,
        ButtonComponent,
        LoginQuestionComponent,
    ],
    imports: [
        CommonModule,
        TranslateModule.forChild({
            extend: true
        }),
    ],
    exports: [
        MessagesComponent,
        ButtonComponent,
        LoginQuestionComponent,
    ],
})
export class SharedModule { }
