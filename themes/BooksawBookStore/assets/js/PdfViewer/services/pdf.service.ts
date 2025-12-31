import { Injectable, Inject } from '@angular/core';
import { Observable } from 'rxjs';

import { HttpClient, HttpHeaders } from '@angular/common/http'
const { context } = require( '../context' );

import { IBookmark } from '../interfaces/bookmarkInterface';

@Injectable({
    providedIn: 'root'
})
export class PdfService
{
    url: string;
    
    constructor(
        @Inject( HttpClient ) private httpClient: HttpClient,
    ) {
        this.url = `${context.backendURL}`;
    }

    getBookmark( bookId: number, bookLocale: String, userId: number ): Observable<IBookmark>
    {
        var url = `${this.url}/bookmarks/${bookId}/${bookLocale}/${userId}`;
        
        return this.httpClient.get<IBookmark>( url );
    }
    
    createBookmark( bookmark: IBookmark ): Observable<IBookmark>
    {
        // alert( 'Creeate Bookmark !!!' );
        var url = `${this.url}/bookmarks/new`;
        
        return this.httpClient.post<IBookmark>( url, bookmark );
    }
}
