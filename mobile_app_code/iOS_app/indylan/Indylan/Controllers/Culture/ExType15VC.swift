//
//  ExType15VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var isUpdateQuestion = true

var paraIndex = 0

var cultureScore = 0

var cultureQuestionCount = 0

var resumeIndex = 0

var arrType15Questions = Array<JSON>()

class ExType15VC: ExerciseController {
   
    @IBOutlet var imgView: UIImageView!
    
    @IBOutlet var lblLink: UILabel!
    
    @IBOutlet var btnLink: UIButton!
    
    @IBOutlet var lblTitle: UILabel!
    
    @IBOutlet var lblText: UILabel!
    
    @IBOutlet var btnContinue: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = selectedCategory

        self.automaticallyAdjustsScrollViewInsets = false
        
        paraIndex = 0
        
        cultureScore = 0
        
        cultureQuestionCount = 0
        
        resumeIndex = 0
        
        isUpdateQuestion = true
        
        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        lblText.textColor = Colors.darkGray
        lblText.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
        
        lblLink.textColor = Colors.darkGray
        lblLink.font = Fonts.centuryGothic(ofType: .regular, withSize: 12)
        
        btnContinue.layer.borderColor = Colors.border.cgColor
        btnContinue.layer.borderWidth = 1
        btnContinue.layer.cornerRadius = 8
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        btnContinue.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnContinue.setTitle("tapContinue".localized(), for: .normal)
        view.bringSubview(toFront: btnContinue)
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        if isUpdateQuestion == true {
            self.updateQuestion()
        }
    }

    // MARK: Functions
    
    func updateQuestion() {
        resumeIndex = 0

        cultureQuestionCount = cultureQuestionCount + arrType15Questions[paraIndex]["questions"].count

        imgView.setImage(withURL: arrType15Questions[paraIndex]["image_path"].stringValue)
        
        lblTitle.text = arrType15Questions[paraIndex]["title_text"].stringValue
        lblText.text = arrType15Questions[paraIndex]["paragraph"].stringValue
        
        lblLink.text = "\(arrType15Questions[paraIndex]["external_link"].stringValue)"
        
        btnContinue.setTitle("tapContinue".localized(), for: UIControlState.normal)
    }

    // MARK: Button Actins
    
    @IBAction func btnLinkClicked(_ sender: Any)
    {
        guard ReachabilityManager.shared.isReachable else {
            SnackBar.show("noInternet".localized())
            return
        }
        let objWebView = UIStoryboard.home.instantiateViewController(withClass: WebViewVC.self)!
        objWebView.strLink = arrType15Questions[paraIndex]["external_link"].stringValue
        self.navigationController?.pushViewController(objWebView, animated: true)
    }
    
    @IBAction func btnContinueClicked(_ sender: Any)
    {
        let objExType15QVC = UIStoryboard.exercise.instantiateViewController(withClass: ExType15QuestinsVC.self)!
        objExType15QVC.arrType15 = arrType15Questions[paraIndex]["questions"].arrayValue
        self.navigationController?.pushViewController(objExType15QVC, animated: true)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
