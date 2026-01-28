import { Injectable } from '@angular/core';

// State
import { StateObject } from './state-object';
import { Busy } from './busy';
import { ErrorState } from './ErrorState';

// Interfaces
import { IUser } from '../interfaces/userInterface';
import MessageDto from '../interfaces/message/messageDto';

// State
import { StatusMessage } from '../utils/status-message';

declare var $: any;

@Injectable({
    providedIn: 'root'
})
export class AppStateService
{
    public static Themes = ['dark', 'light', 'blue', 'pink', 'green'];
    
    busy: StateObject<Busy>;
    
    moveTimer: StateObject<number>;
    errors: StateObject<ErrorState>;
    messages: StateObject<MessageDto[]>;
    theme: StateObject<string>;
    
    user: StateObject<IUser>;
    statusMessage: StateObject<StatusMessage>;
    
    constructor()
    {
        this.busy = new StateObject<Busy>();
        
        this.user = new StateObject<IUser>();
        this.statusMessage = new StateObject<StatusMessage>();
        this.moveTimer = new StateObject<number>();
        this.errors = new StateObject<ErrorState>();
        this.messages = new StateObject<MessageDto[]>();
        this.messages.setValue( [] );
        this.theme = new StateObject<string>();
        this.theme.setValue( 'dark' );
    }
    
    showBusy(): void
    {
        this.busy.setValue( new Busy( 'Please wait', true ) );
    }
    
    hideBusy(): void
    {
        this.busy.clearValue();
    }
    
    showBusyNoOverlay(): void
    {
        this.busy.setValue( new Busy( 'Please wait', false ) );
    }
    
    changeTheme( theme: string ): void
    {
        if ( ! theme || theme.length === 0 ) theme = 'dark';
        
        AppStateService.Themes.forEach( ( v ) => {
            $( '#GameContainer' ).removeClass( v );
        });
        
        $( '#GameContainer' ).addClass( theme );
        this.theme.setValue( theme );
    }
}
