//
//  ChooseSubCategoryVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

var selectedSubCategoryId = ""

class ChooseSubCategoryVC: ThemeViewController, UITableViewDelegate, UITableViewDataSource {

    //MARK: DECLARATION
    
    @IBOutlet var tblViewSubCategory: TableView!

    var arrSubCategory = Array<JSON>()
    
    var isRefreshRequires = false
    
    override func viewDidLoad() {
        super.viewDidLoad()

        navigationItem.setTitle(title: "chooseSubCategory".localized(), subtitle: selectedExercise.uppercased())

        tblViewSubCategory.register(UINib(nibName: "CellSingleSelection", bundle: nil), forCellReuseIdentifier: "cellSelection")
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
        if isRefreshRequires {
            getSubCategoryList()
        }
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        isRefreshRequires = true
    }
    
    //MARK: UITABLEVIEW DELEGATE METHODS
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrSubCategory.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell
    {
        let cellSelection = tblViewSubCategory.dequeueReusableCell(withIdentifier: "cellSelection") as! CellSingleSelection

//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27"
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.right
//        }
//        else
//        {
//            cellSelection.lblTitle.textAlignment = NSTextAlignment.left
//        }

        cellSelection.lblTitle.text = arrSubCategory[indexPath.row]["subcategory_name"].stringValue
        
        cellSelection.viewStar.isHidden = false
        
        let imageURL = arrSubCategory[indexPath.row]["image_path"].stringValue
        cellSelection.imgView.isHidden = imageURL.isEmpty
        cellSelection.imgView.setImage(withURL: imageURL)
        
        cellSelection.viewStar.isHidden = isGuestUser
        
        if !isGuestUser {
            cellSelection.viewStar.rating = arrSubCategory[indexPath.row]["ratting"].doubleValue / 5
        }
        
        return cellSelection
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        selectedSubCategoryId = arrSubCategory[indexPath.row]["exercise_mode_subcategory_id"].stringValue

        TapticEngine.impact.feedback(.light)
        
        navigateToNextView()
    }

    //MARK: Functions

    func getSubCategoryList()
    {
        if ReachabilityManager.shared.isReachable
        {
            var userId: String = "0"
            
            if let user = currentUser {
                userId = user.userId
            }

            let dictParam = [
                "user_id"       : userId,
                "category_id"   : selectedCategoryId,
            ]

            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_subcategory_list") as String, params: dictParam, completion: { (status, resObj) in
                    
                    if status && ((resObj["status"].intValue) == 1) && !resObj["result"].isEmpty {
                
                        self.arrSubCategory = resObj["result"].arrayValue
                        self.tblViewSubCategory.reloadData()
                        
                    } else {
                        if !resObj["message"].stringValue.isEmpty {
                            SnackBar.show("\(resObj["message"])")
                        } else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        } else {
            SnackBar.show("noInternet".localized())
        }
    }
    
    //MARK: BUTTON ACTIONS
    
    func navigateToNextView() {
        
        if ReachabilityManager.shared.isReachable {
            
            let dictParam = [
                "subcategory_id" : selectedSubCategoryId
            ]
            
            APIManager.shared.request(strURL: String(format:"\(Environment.APIPath)/get_exercise_type") as String, params: dictParam, completion: { (status, resObj) in
                    
                    if status && ((resObj["status"].intValue) == 1) && !resObj["result"].isEmpty {
                        
                        let objChooseExType = UIStoryboard.home.instantiateViewController(withClass: ChooseExTypeVC.self)!
                        objChooseExType.arrExerciseType = resObj["result"].arrayValue
                        self.navigationController?.pushViewController(objChooseExType, animated: true)
                        
                    } else {
                        if !resObj["message"].stringValue.isEmpty {
                            SnackBar.show("\(resObj["message"])")
                        } else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        } else {
            SnackBar.show("noInternet".localized())
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

}
