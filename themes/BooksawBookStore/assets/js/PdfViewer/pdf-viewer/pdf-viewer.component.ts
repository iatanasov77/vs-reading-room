import {Component, OnInit, ViewChild} from '@angular/core';

import templateString from './pdf-viewer.component.html'
import cssString from './pdf-viewer.component.scss'

declare var $: any;

@Component({
    selector: 'app-pdf-viewer',
    template: templateString || 'Template Not Loaded !!!',
    styles: [cssString || 'Template Not Loaded !!!',]
})
export class PdfViewerComponent implements OnInit
{
    @ViewChild( 'bigPdfViewer', { static: true } ) public bigPdfViewer: any;

    pdfUrl: String;
    
    constructor()
    {
        this.pdfUrl = '';
    }
    
    ngOnInit(): void
    {
        this.pdfUrl = $( '#ReadBookContainer' ).attr( 'data-BookUrl' );
    }
    
    public testBeforePrint(): void
    {
        console.log( 'testBeforePrint() successfully called' );
        console.log( this.bigPdfViewer.page );
        
        this.bigPdfViewer.page = 3;
        console.log( this.bigPdfViewer.page );
    }
    
    public testAfterPrint(): void
    {
        console.log( 'testAfterPrint() successfully called' );
    }
    
    public testPagesLoaded( count: number ): void
    {
        console.log( 'testPagesLoaded() successfully called. Total pages # : ' + count );
    }
    
    public testPageChange( pageNumber: number ): void
    {
        console.log( 'testPageChange() successfully called. Current page # : ' + pageNumber );
    }
}