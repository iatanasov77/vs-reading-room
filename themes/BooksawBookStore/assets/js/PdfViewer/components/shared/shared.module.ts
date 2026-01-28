import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

import { MessagesComponent } from './messages/messages.component';

@NgModule({
    declarations: [
        MessagesComponent
    ],
    imports: [
        CommonModule,
        TranslateModule.forChild({
            extend: true
        }),
    ],
    exports: [
        MessagesComponent,
    ],
})
export class SharedModule { }
