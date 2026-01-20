import { Component, Inject, ViewChild, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { PdfJsViewerComponent } from "ng2-pdfjs-viewer";

// Services
import { PdfService } from '../services/pdf.service';

// Interfaces
import { IUser } from '../interfaces/userInterface';
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
    
    user?: IUser;
    
    /**
     * pdfjs Viewer Settings 
     */
    openFile: Boolean;
    viewBookmark: Boolean;
    download: Boolean;
    print: Boolean;
    
    constructor(
        @Inject( TranslateService ) private translate: TranslateService,
        @Inject( PdfService ) private pdfService: PdfService,
    ) {
        this.bookId         = 0;
        this.bookLocale     = 'en_US';
        
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
        
        this.pdfUrl         = $( '#ReadBookContainer' ).attr( 'data-BookUrl' );
        this.bookFileName   = $( '#ReadBookContainer' ).attr( 'data-BookFileName' );
        this.openFile       = $( '#ReadBookContainer' ).attr( 'data-openFile' );
        this.viewBookmark   = $( '#ReadBookContainer' ).attr( 'data-viewBookmark' );
        this.download       = $( '#ReadBookContainer' ).attr( 'data-download' );
        this.print          = $( '#ReadBookContainer' ).attr( 'data-print' );
        this.locale         = $( '#ReadBookContainer' ).attr( 'data-locale' );
        
        const lang = this.locale.split( "-" );
        this.translate.use( lang[0] );
        //alert( lang[0] );
        
        var userId          = $( '#ReadBookContainer' ).attr( 'data-UserId' );
        if ( userId > 0 ) {
            this.user = {
                id: userId,
                name: $( '#ReadBookContainer' ).attr( 'data-UserName' ),
                email: $( '#ReadBookContainer' ).attr( 'data-UserEmail' ),
                autoBookmark: ( $( '#ReadBookContainer' ).attr( 'data-UserAutoBookmark' ) == "true" )
            };
        }
        
        /*
        $( window ).on( 'beforeunload', function() {
            return "Component Destroyed !!!";
        });
        */
        /*
        window.addEventListener( "beforeunload", function ( e ) {
            var confirmationMessage = "Component Destroyed !!!";
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        });
        */​
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
        
        if ( this.user ) {
            this.pdfService.getBookmark( this.bookId, this.bookLocale, this.user.id ).subscribe( ( bookmark: IBookmark ) => {
                this.bigPdfViewer.page = bookmark.page;
            });
        }
    }
    
    public pageChange( pageNumber: number ): void
    {
        console.log( 'testPageChange() successfully called. Current page # : ' + pageNumber );
        //alert( `Book ID: ${this.bookId} User ID: ${this.userId}` );
        
        if ( this.user && this.user.autoBookmark ) {
            this.createBookmark( pageNumber );
        }
    }
    
    public createBookmark( pageNumber: number ): void
    {
        if ( ! this.user ) {
            return;
        }
        
        const bookmark: IBookmark = {
            dateCreated: new Date(), // Date.now(),
            bookId: this.bookId,
            bookLocale: this.bookLocale,
            userId: this.user.id,
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
    
    public clickCreateBookmark(): void
    {
        this.createBookmark( this.bigPdfViewer.page );
    }
}