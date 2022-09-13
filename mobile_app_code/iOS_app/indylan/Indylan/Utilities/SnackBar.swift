//
//  SnackBar.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation

class SnackBar: NSObject {
    
    // MARK: - Class Properties
    
    private static let snackbar: TTGSnackbar = TTGSnackbar()
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Static Functions
    
    static func show(_ message: String,
                      duration: TTGSnackbarDuration = .middle) {
        
        guard !message.isEmpty else { return }
        
        snackbar.message = message
        snackbar.duration = duration
        
        snackbar.bottomMargin = 22
        
        snackbar.onTapBlock = { snackbar in
            snackbar.dismiss()
        }
        
        snackbar.show()
    }
    
    // -----------------------------------------------------------------------------------------------
}
