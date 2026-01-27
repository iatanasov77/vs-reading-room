import { Injectable, Inject } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { StatusMessage } from '../utils/status-message';

import { AppStateService } from '../state/app-state.service';

@Injectable({
    providedIn: 'root'
})
export class StatusMessageService
{
    constructor(
        @Inject( TranslateService ) private trans: TranslateService,
        @Inject( AppStateService ) private appState: AppStateService,
    ) {
        this.trans.use( 'en' );
    }
    
    setNotLoggedIn(): void
    {
        const text = this.trans.instant( 'statusmessage.youarenotloggedin' );
        const msg = StatusMessage.info( text );
        this.appState.statusMessage.setValue( msg );
        alert( msg.text );
    }
}
