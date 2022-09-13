//
//  APIManager.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import Foundation
import Alamofire

class APIManager {
    
    static let shared = APIManager()
    
    func request(strURL: String,
                 method: HTTPMethod = .post,
                 params: [String: Any]? = nil,
                 withErrorAlert isErrorAlert: Bool = false,
                 withLoader isLoader: Bool = true,
                 withDebugLog isDebug: Bool = true,
                 completion: @escaping (_ isSuccess: Bool, _ response: JSON) -> ()) {
        
        if isLoader {
            Indicator.show()
        }
        
        var parameters: [String: Any]? = params
        
        if !selectedSupportLanguageId.isEmpty {
            parameters?["support_lang_id"] = selectedSupportLanguageId
        }
        
//        if !selectedMenuLanguageId.isEmpty {
//            parameters?["lang"] = selectedMenuLanguageId
//        }
        
        if !selectedTargetLanguageId.isEmpty {
            parameters?["target_lang"] = selectedTargetLanguageId
            parameters?["lang"] = selectedTargetLanguageId
        }
        
        if isDebug {
            Log.shortLine()
            Log.server("Request URL:\n\n\(strURL)\n")
            Log.shortLine()
            Log.server("Request Parameters:\n\n\(parameters as AnyObject)\n")
        }
        
        UIApplication.shared.isNetworkActivityIndicatorVisible = true

        Alamofire.request(strURL, method: method, parameters: parameters, encoding: URLEncoding.default, headers: nil).responseJSON{ (responseData) -> Void in
            
            DispatchQueue.main.async {
                if isLoader {
                    Indicator.hide()
                }
                
                UIApplication.shared.isNetworkActivityIndicatorVisible = false
            }
            
            if responseData.result.isSuccess {
                
                let josn = JSON(responseData.result.value!)
                
                if isDebug {
                    Log.shortLine()
                    Log.server("Response Data:\n\n\(josn as AnyObject)\n")
                    Log.shortLine()
                }
                
                completion(true, josn)
                
            } else if responseData.result.isFailure {
                
                let error = responseData.result.error!
                
                if isDebug {
                    Log.shortLine()
                    Log.error("Response Error\n\n\(error.localizedDescription)")
                    Log.shortLine()
                }
                
                if isErrorAlert {
                    Alert.showWith("Error", message: error.localizedDescription, positiveTitle: "OK", completion: nil)
                }
                
                completion(false, JSON(responseData.error!))
            }
        }
    }

}
