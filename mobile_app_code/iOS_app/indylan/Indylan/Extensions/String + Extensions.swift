//
//  String + Extensions.swift
//  Indylan
//
//  Created by Bhavik Thummar on 11/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation

extension String {
    
    func localized() -> String {
        let path = Bundle.main.path(forResource: UserDefaults.standard.object(forKey: "selectedLanguageCode") as? String, ofType: "lproj") ?? Bundle.main.path(forResource: "en", ofType: "lproj")
        let bundle = Bundle(path: path!)
        return NSLocalizedString(self, tableName: nil, bundle: bundle!, value: "", comment: "")
    }
}
