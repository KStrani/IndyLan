//
//  Environment.swift
//  Indylan
//
//  Created by Bhavik Thummar on 30/03/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation

// MARK: - App Environment -

final class Environment {
    
    // MARK: - Class Enums
    
    enum Server {
        case developement
        case staging
        case production
    }
    
    // -----------------------------------------------------------------------------------------------

    // MARK: - Class Properties
    
    static let server: Server = .staging

    static var APIPath: String {
        APIBasePath + APIMiddlePath
    }

    static var APIBasePath: String {
//        http://admin.indylan.eu/api
//        http://lango.alphademo.in/
        http://lango.alphademo.in/indylan/api/
        //http://admin.indylan.eu
        switch self.server {
            case .developement:
                return "http://admin.indylan.eu"
            case .staging:
                return "https://admin.indylan.eu"
            case .production:
                return "https://admin.indylan.eu"
        }
    }
    
    static var APIMiddlePath: String {
//        indylan/api
        switch self.server {
        case .developement:
            return "/api"
        case .staging:
            return "/api"
        case .production:
            return "/api"
        }
    }

    // -----------------------------------------------------------------------------------------------
    
    // MARK: - Class Initializers
    
    private init() {}
    
    // -----------------------------------------------------------------------------------------------
}

// -----------------------------------------------------------------------------------------------

