//
//  AboutUsVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class AboutUsVC: ThemeViewController {
  
    @IBOutlet weak var baseScrollView: BaseScrollView!
    
    @IBOutlet var txtViewAboutUs: UITextView!
    
    @IBOutlet var constraintHeightTxtView: NSLayoutConstraint!
    
    @IBOutlet var lblUrl: UILabel!
    
    @IBOutlet var btnUrl: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = "ABOUT US"
        
        removeProfileButton()
                
        txtViewAboutUs.text =
        """
        Mobile Virtual Learning for Indigenous Languages
        Project Reference: 2019-1-UK01-KA204-061875
        """
        
        constraintHeightTxtView.constant = txtViewAboutUs.contentSize.height

        navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)
    }

    @IBAction func btnUrlEvent(_ sender: Any) {
        guard ReachabilityManager.shared.isReachable else {
            SnackBar.show("noInternet".localized())
            return
        }
        let objWebView = UIStoryboard.home.instantiateViewController(withClass: WebViewVC.self)!
        objWebView.strLink = "http://www.folkuniversitetet.se"
        objWebView.strTitle = AppName
        self.navigationController?.pushViewController(objWebView, animated: true)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
