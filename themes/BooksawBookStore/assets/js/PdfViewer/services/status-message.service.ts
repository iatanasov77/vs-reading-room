import { Injectable, Inject } from '@angular/core';

import { StatusMessage } from '../utils/status-message';
import { AppStateService } from '../state/app-state.service';

@Injectable({
    providedIn: 'root'
})
export class StatusMessageService
{
    constructor(
        @Inject( AppStateService ) private appState: AppStateService,
    ) {}
    
    setNotLoggedIn(): void
    {
        const msg = StatusMessage.info( 'statusmessage.youarenotloggedin' );
        this.appState.statusMessage.setValue( msg );
    }
}
