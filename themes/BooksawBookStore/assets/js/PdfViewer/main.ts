import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { PdfViewerModule } from './pdf-viewer/pdf-viewer.module';

const {context} = require( './context' );
if ( context.isProduction ) {
    enableProdMode();
}

platformBrowserDynamic().bootstrapModule(PdfViewerModule)
                        .catch( ( err: any ) => console.error( err ) );
