//
//  CongratsVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class CongratsVC: ExerciseController {

    //MARK: DECLARATION
    
    @IBOutlet var lblCongrats: UILabel!
    
    @IBOutlet var lblTaskCompleted: UILabel!
    
    @IBOutlet weak var vwScore: UIView!
    @IBOutlet var lblScore: UILabel!
    
    @IBOutlet var btnRateUs: UIButton!
    
    @IBOutlet var lblgreatJob: UILabel!

    @IBOutlet var btnReturn: BaseButton!
    @IBOutlet var btnRetry: BaseButton!
    @IBOutlet weak var bottomConstraint: NSLayoutConstraint!
    
    var score = 456
    
    var totalQuestions = 456

    override func viewDidLoad() {
        
        super.viewDidLoad()

        self.title = "congratulations".localized().uppercased()
        
        removeBackButton()
        removeProfileButton()
        
        lblCongrats.textColor = Colors.black
        lblCongrats.font = Fonts.centuryGothic(ofType: .bold, withSize: 15)
        
        lblTaskCompleted.textColor = Colors.black
        lblTaskCompleted.font = Fonts.centuryGothic(ofType: .regular, withSize: 15)
        
        vwScore.backgroundColor = Colors.yellow
        vwScore.layer.cornerRadius = 8
        
        lblScore.textColor = Colors.white
        lblScore.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        btnReturn.backgroundColor = Colors.skyBlue
        btnRetry.backgroundColor = Colors.blue
        
        lblgreatJob.textColor = Colors.black
        lblgreatJob.font = Fonts.centuryGothic(ofType: .bold, withSize: 18)
        
        lblCongrats.text = "congratulations".localized()
        lblTaskCompleted.text = "taskCompleted".localized()
        lblgreatJob.text = "greatJob".localized()
        btnReturn.setTitle("return".localized(), for: UIControlState.normal)
        btnRetry.setTitle("retry".localized(), for: UIControlState.normal)
        btnRateUs.setTitle("rateUsNow".localized(), for: UIControlState.normal)
        
        bottomConstraint.constant = ScreenHeight * 0.14
        
        if score != 456 {
            lblScore.text = "\("totalScore".localized()): \(String(format: "%d / %d", score, totalQuestions))"
        } else {
            score = totalQuestions
            vwScore.isHidden = true
        }
        
        if selectedExerciseId != "1111" {
            updateScrore()
        }
    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        self.addBarButtons()
    }

    // MARK : Functions
    
    private func addBarButtons() {
        addBackButton()
        addProfileButton()
    }
    
    func updateScrore() {
        
        if ReachabilityManager.shared.isReachable {
            var userId: String = "0"
            
            if let user = currentUser {
                userId = user.userId
            }

            var dictParam: [String: Any] = [
                "user_id"       : userId,
                "total_score"   : totalQuestions,
                "correct_score" : score,
                "category_id"   : selectedCategoryId,
                "subcategory_id": selectedSubCategoryId,
                "type_id"       : selectedExTypeId
            ]

            if isGuestUser {
                let tempId = UserDefaults.standard.value(forKey: "temp_user_id") ?? 0
                dictParam["temp_id"] = tempId
            }

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/submit_user_score") as String, params: dictParam, completion:
                { (status, resObj) in
            })
        }
        else
        {
            SnackBar.show("noInternet".localized())
        }
    }

    //MARK: BUTTON ACTIONS
    
    @IBAction func btnRateUs(_ sender: UIButton) {
        if let url = URL(string: "https://apps.apple.com/us/app/indylan-learn-indigenous-langs/id1590288935"),
            UIApplication.shared.canOpenURL(url) {
            if #available(iOS 10.0, *) {
                UIApplication.shared.open(url)
            } else {
                UIApplication.shared.openURL(url)
            }
        }
    }

    @IBAction func btnRetryAction(_ sender: Any) {
        
        let viewControllers = self.navigationController!.viewControllers
        
        for aViewController in viewControllers {
            if selectedExerciseId == "1111" {
                if(aViewController is TestVC) {
                    self.navigationController!.popToViewController(aViewController, animated: true)
                    return
                }
            } else {
                if let chooseExTypeVC = aViewController as?  ChooseExTypeVC {
                    guard (self.navigationController != nil) else {
                        return
                    }
                    
                    self.navigationController?.popToViewController(chooseExTypeVC, animated: false)
                    chooseExTypeVC.navigateToNextView(Type: "")
                    return
                }
            }
        }
    }
    
    @IBAction func btnReturnAction(_ sender: UIButton) {
        
        guard (self.navigationController != nil) else {
            return
        }
        
        let viewControllers = self.navigationController!.viewControllers
        
        for aViewController in viewControllers {
            
            if selectedExerciseId == "1111" {
                if(aViewController is SelectExerciseModeVC) {
                    self.navigationController!.popToViewController(aViewController, animated: true);
                }
            } else {
                if(aViewController is ChooseExTypeVC) {
                    guard (self.navigationController != nil) else {
                        return
                    }
                    self.navigationController!.popToViewController(aViewController, animated: true);
                }
            }
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
