import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { APP_BASE_HREF } from '@angular/common';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

// ----> Import PdfJsViewerModule here
import { PdfJsViewerModule } from 'ng2-pdfjs-viewer';
import { MatToolbarModule } from '@angular/material/toolbar';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatButtonModule as MatButtonModule } from '@angular/material/button';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatTableModule as MatTableModule } from '@angular/material/table';

import { PdfViewerComponent } from './pdf-viewer/pdf-viewer.component';
import { MessagesComponent } from './messages/messages.component';

const MATERIAL_IMPORTS = [
    BrowserAnimationsModule,
    MatToolbarModule,
    MatButtonModule
];

export function HttpLoaderFactory( http: HttpClient ) {
    return new TranslateHttpLoader( http, '/build/booksaw-book-store/i18n/', '.json' );
}

@NgModule({
    declarations: [
        PdfViewerComponent,
        MessagesComponent
    ],
    imports: [
        BrowserModule,
        PdfJsViewerModule,
        MatGridListModule,
        MatTableModule,
        MATERIAL_IMPORTS,
        
        HttpClientModule,
        TranslateModule.forRoot({
            defaultLanguage: 'en',
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })
    ],
    providers: [
        { provide: APP_BASE_HREF, useValue: window.location.pathname }
    ],
    bootstrap: [PdfViewerComponent]
})
export class PdfViewerModule { }
