import { Component, Inject, ViewChild, OnInit } from '@angular/core';
import { PdfJsViewerComponent } from "ng2-pdfjs-viewer";

// Services
import { PdfService } from '../services/pdf.service';

// Interfaces
import { IBookmark } from '../interfaces/bookmarkInterface';

import templateString from './pdf-viewer.component.html'
import cssString from './pdf-viewer.component.scss'

declare var $: any;

@Component({
    selector: 'app-pdf-viewer',
    template: templateString || 'Template Not Loaded !!!',
    styles: [cssString || 'Template Not Loaded !!!',],
    standalone: false
})
export class PdfViewerComponent implements OnInit
{
    @ViewChild( 'bigPdfViewer' ) bigPdfViewer!: PdfJsViewerComponent;

    pdfUrl: String;
    bookFileName: String;
    locale: String;
    page: number;
    
    bookId: number;
    bookLocale: String;
    userId: number;
    
    /**
     * pdfjs Viewer Settings 
     */
    openFile: Boolean;
    viewBookmark: Boolean;
    download: Boolean;
    print: Boolean;
    
    constructor(
        @Inject( PdfService ) private pdfService: PdfService,
    ) {
        this.bookId         = 0;
        this.bookLocale     = 'en_US';
        this.userId         = 0;
        
        this.pdfUrl         = '';
        this.bookFileName   = '';
        
        this.locale         = 'en-US';
        this.page           = 1;
        
        this.openFile       = false;
        this.viewBookmark   = false;
        this.download       = false;
        this.print          = false;
    }
    
    ngOnInit(): void
    {
        this.bookId         = $( '#ReadBookContainer' ).attr( 'data-BookId' );
        this.bookLocale     = $( '#ReadBookContainer' ).attr( 'data-BookLocale' );
        this.userId         = $( '#ReadBookContainer' ).attr( 'data-UserId' );
        
        this.locale         = $( '#ReadBookContainer' ).attr( 'data-locale' );
        
        this.pdfUrl         = $( '#ReadBookContainer' ).attr( 'data-BookUrl' );
        this.bookFileName   = $( '#ReadBookContainer' ).attr( 'data-BookFileName' );
        this.openFile       = $( '#ReadBookContainer' ).attr( 'data-openFile' );
        this.viewBookmark   = $( '#ReadBookContainer' ).attr( 'data-viewBookmark' );
        this.download       = $( '#ReadBookContainer' ).attr( 'data-download' );
        this.print          = $( '#ReadBookContainer' ).attr( 'data-print' );
        
        /*
        $( window ).on( 'beforeunload', function() {
            return "Component Destroyed !!!";
        });
        */
        window.addEventListener( "beforeunload", function ( e ) {
            var confirmationMessage = "Component Destroyed !!!";
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        });​
    }
    
    public beforePrint(): void
    {
        console.log( 'testBeforePrint() successfully called' );
        console.log( this.bigPdfViewer.page );
        
        this.bigPdfViewer.page = 3;
        console.log( this.bigPdfViewer.page );
    }
    
    public afterPrint(): void
    {
        console.log( 'testAfterPrint() successfully called' );
    }
    
    public pagesLoaded( count: number ): void
    {
        console.log( 'testPagesLoaded() successfully called. Total pages # : ' + count );
        
        if ( this.userId > 0 ) {
            this.pdfService.getBookmark( this.bookId, this.bookLocale, this.userId ).subscribe( ( bookmark: IBookmark ) => {
                this.bigPdfViewer.page = bookmark.page;
            });
        }
    }
    
    public pageChange( pageNumber: number ): void
    {
        console.log( 'testPageChange() successfully called. Current page # : ' + pageNumber );
        //alert( `Book ID: ${this.bookId} User ID: ${this.userId}` );
        
        if ( this.userId > 0 ) {
            const bookmark: IBookmark = {
                dateCreated: new Date(), // Date.now(),
                bookId: this.bookId,
                bookLocale: this.bookLocale,
                userId: this.userId,
                page: pageNumber
            };
            
            this.pdfService.createBookmark( bookmark ).subscribe({
                next: ( response: any ) => {
                    // this.closeModal.emit();
                },
                error: ( err: any ) => {
                    console.error( err );
                }
            });
        }
    }
    
    public createBookmark( bookmark: any ): void
    {
        console.log( 'testCreateBookmark() successfully called. Bookmark # : ' + bookmark );
    }
}