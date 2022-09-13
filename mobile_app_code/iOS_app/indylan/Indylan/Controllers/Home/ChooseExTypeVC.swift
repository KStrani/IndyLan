//
//  ChooseExTypeVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var selectedExTypeId = ""

class ChooseExTypeVC: ThemeViewController, UITableViewDataSource, UITableViewDelegate {
    
    //MARK: DECLARATION
    
    @IBOutlet weak var tblViewChooseExercise: TableView!
    
    var arrExerciseType = Array<JSON>()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        addBackButton()
        addProfileButton()
        
        navigationItem.setTitle(title: "chooseExerciseType".localized(), subtitle: selectedExercise.uppercased())
        
        tblViewChooseExercise.register(UINib(nibName: "ListCell", bundle: nil), forCellReuseIdentifier: "ListCell")
    }

//    override func viewWillAppear(_ animated: Bool) {
//        super.viewWillAppear(animated)
//
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27" {
//            UIView.appearance().semanticContentAttribute = .forceRightToLeft
//            navigationController?.navigationBar.semanticContentAttribute = .forceRightToLeft
//        } else {
//            UIView.appearance().semanticContentAttribute = .forceLeftToRight
//            navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
//        }
//    }

    //MARK: UITABLEVIEW DELEGATE METHODS
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrExerciseType.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cellSelection = tblViewChooseExercise.dequeueReusableCell(withIdentifier: "ListCell") as! ListCell

//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = .right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = .left
//        }
        
        cellSelection.lblTitle.text =  arrExerciseType[indexPath.row]["type_name"].stringValue
        
        let imageURL = arrExerciseType[indexPath.row]["image"].stringValue
        cellSelection.imgIcon.isHidden = imageURL.isEmpty
        cellSelection.imgIcon.setImage(withURL: imageURL)
        
        return cellSelection
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        selectedExTypeId = arrExerciseType[indexPath.row]["id"].stringValue
        
        TapticEngine.impact.feedback(.light)
        
        navigateToNextView(Type: arrExerciseType[indexPath.row]["type_name"].stringValue)
    }
    
    // MARK:- BUTTON ACTIONS
    
    func navigateToNextView(Type : String) {
        
        if ReachabilityManager.shared.isReachable {
            
            var strEx = ""
            
            switch selectedExerciseId {
                case "1": strEx = "vocabulary"
                case "2": strEx = "dialogues"
                case "3": strEx = "phrases"
                case "4": strEx = "grammar"
                case "5": strEx = "culture"
                case "6": strEx = "aural"
                default: strEx = ""
            }
            
            let dictParam = [
                "exercise_mode_id"  : selectedExerciseId,
                "category_id"       : selectedCategoryId,
                "subcategory_id"    : selectedSubCategoryId,
                "type"              : selectedExTypeId
            ]
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/\(strEx)_exercise") as String, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status == true
                    {
                        if (resObj["status"].intValue) == 1
                        {
                            if resObj["result"].count > 0
                            {
                                UIView.appearance().semanticContentAttribute = .forceLeftToRight
                                
                                self.navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
                                
                                switch selectedExTypeId {
                                    
                                case "1", "2", "3":
        
                                        let objExType1 = UIStoryboard.exercise.instantiateViewController(withClass: ExType1VC.self)!
                                        objExType1.arrType1Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType1, animated: true)
                                        
                                        break
                                    
                                    case "4", "5":
                                        
                                        let objExType4 = UIStoryboard.exercise.instantiateViewController(withClass: ExType4VC.self)!
                                        objExType4.arrType4Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType4, animated: true)
                                        
                                        break
                                        
                                    case "6":

                                        let objExType6 = UIStoryboard.exercise.instantiateViewController(withClass: ExType6VC.self)!
                                        objExType6.arrType6Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType6, animated: true)
                                        
                                        break
                                        
                                    case "7":
                                        let objExType7 = UIStoryboard.exercise.instantiateViewController(withClass: ExType7VC.self)!
                                        objExType7.arrType7Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType7, animated: true)
                                        
                                        break
                                    
                                    case "8":
                                        let objExType8 = UIStoryboard.exercise.instantiateViewController(withClass: ExType8VC.self)!
                                        objExType8.arrType8Questions = resObj["result"].arrayValue
                                        
                                        self.navigationController?.pushViewController(objExType8, animated: true)
                                        
                                        break
                                    
                                    case "9":
                                        let objExType4 = UIStoryboard.exercise.instantiateViewController(withClass: ExType4VC.self)!
                                        objExType4.arrType4Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType4, animated: true)
                                        
                                        break
                                    
                                    case "10":
                                        let objExType10 = UIStoryboard.exercise.instantiateViewController(withClass: ExType10VC.self)!
                                        objExType10.arrType10Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType10, animated: true)
                                        
                                        break
                                    
                                    case "11":
                                        
                                        let objExType11 = UIStoryboard.exercise.instantiateViewController(withClass: ExType11VC.self)!
                                        objExType11.arrType11Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType11, animated: true)
                                    
                                    case "12":
                                        
                                        let objExType12 = UIStoryboard.exercise.instantiateViewController(withClass: ExType12VC.self)!
                                        objExType12.arrType12Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType12, animated: true)
                                        
                                        break
                                    
                                    case "13", "14":
                                        let objExType13 = UIStoryboard.exercise.instantiateViewController(withClass: ExType13VC.self)!
                                        objExType13.arrType13Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType13, animated: true)
                                        
                                        break
                                    
                                    case "15":
                                        
                                        let objExType15 = UIStoryboard.exercise.instantiateViewController(withClass: ExType15VC.self)!
                                        arrType15Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType15, animated: true)
                                        break
                                    
                                case "16":
                                    
                                    let objExType15 = UIStoryboard.exercise.instantiateViewController(withClass: ExType4VC.self)!
                                    objExType15.arrType4Questions = resObj["result"].arrayValue
                                    self.navigationController?.pushViewController(objExType15, animated: true)
                                    break
                                case "17","18":
                                    
                                    let objExType15 = UIStoryboard.exercise.instantiateViewController(withClass: ExType1VC.self)!
                                    objExType15.btnName = Type
                                    objExType15.isFromNewSelection = true
                                    objExType15.arrType1Questions = resObj["result"].arrayValue
                                    self.navigationController?.pushViewController(objExType15, animated: true)
                                    break
                                    default:
                                        SnackBar.show("Some unexpected error has occured")
                                        break
                                }
                            }
                            else
                            {
                                SnackBar.show(resObj["message"].stringValue)
                            }
                        }
                        else
                        {
                            SnackBar.show(resObj["message"].stringValue)
                        }
                    }
                    else
                    {
                        if resObj["message"].stringValue.count > 0
                        {
                            SnackBar.show("\(resObj["message"])")
                        }
                        else
                        {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        }
        else {
            SnackBar.show("noInternet".localized())
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
