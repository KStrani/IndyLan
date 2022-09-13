//
//  CurrentUser.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CurrentUser {
    
    enum LoginType: String {
        case normal     = "0"
        case facebook   = "1"
        case google     = "2"
        case apple      = "3"
        case unknown    = ""
    }
    
    let osType      : String
    let profilePic  : String
    let userId      : String
    let updatedAt   : String
    let socialPic   : String
    let password    : String
    let firstName   : String
    let type        : String
    let socialId    : String
    let resetToken  : String
    let createdAt   : String
    let isCctive    : String
    let email       : String
    let score       : String
    let loginType   : LoginType

    init(withJSON json: JSON) {
        osType          = json["os_type"].stringValue
        profilePic      = json["profile_pic"].stringValue
        userId          = json["user_id"].stringValue
        updatedAt       = json["updated_at"].stringValue
        socialPic       = json["social_pic"].stringValue
        password        = json["password"].stringValue
        firstName       = json["first_name"].stringValue
        type            = json["type"].stringValue
        socialId        = json["social_id"].stringValue
        resetToken      = json["reset_token"].stringValue
        createdAt       = json["created_at"].stringValue
        isCctive        = json["is_active"].stringValue
        email           = json["email"].stringValue
        score           = json["score"].stringValue
        loginType       = LoginType(rawValue: json["social_type"].stringValue) ?? .unknown
    }
}
