import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { APP_BASE_HREF } from '@angular/common';

// ----> Import PdfJsViewerModule here
import { PdfJsViewerModule } from 'ng2-pdfjs-viewer';
import { MatToolbarModule } from '@angular/material/toolbar';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatButtonModule as MatButtonModule } from '@angular/material/button';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatTableModule as MatTableModule } from '@angular/material/table';

import { PdfViewerComponent } from './pdf-viewer.component';

const MATERIAL_IMPORTS = [
    BrowserAnimationsModule,
    MatToolbarModule,
    MatButtonModule
];

@NgModule({
    declarations: [
        PdfViewerComponent
    ],
    imports: [
        BrowserModule,
        PdfJsViewerModule,
        MatGridListModule,
        MatTableModule,
        MATERIAL_IMPORTS
    ],
    providers: [
        { provide: APP_BASE_HREF, useValue: window.location.pathname }
    ],
    bootstrap: [PdfViewerComponent]
})
export class PdfViewerModule { }
